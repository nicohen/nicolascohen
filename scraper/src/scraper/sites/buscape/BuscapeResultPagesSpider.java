package scraper.sites.buscape;

import java.util.ArrayList;
import java.util.Calendar;
import java.util.List;
import java.util.StringTokenizer;

import org.htmlparser.Node;
import org.htmlparser.NodeFilter;
import org.htmlparser.filters.AndFilter;
import org.htmlparser.filters.HasAttributeFilter;
import org.htmlparser.filters.LinkStringFilter;
import org.htmlparser.filters.NodeClassFilter;
import org.htmlparser.filters.NotFilter;
import org.htmlparser.filters.OrFilter;
import org.htmlparser.filters.TagNameFilter;
import org.htmlparser.nodes.TagNode;
import org.htmlparser.tags.LinkTag;
import org.htmlparser.util.ParserException;
import org.htmlparser.util.SimpleNodeIterator;
import org.htmlparser.util.Translate;

import scraper.AbstractResultPagesSpider;
import dto.AttributeDto;
import dto.ProductDto;
import dto.ResultPageDto;

public class BuscapeResultPagesSpider extends AbstractResultPagesSpider {

	public BuscapeResultPagesSpider() {
		super();
	}
	
	@Override
	public boolean hasNext() {
		// http://precio2.buscape.com.ar/camara-fotografica-digital-pg2.html
	    NodeFilter filter = new AndFilter(new NodeClassFilter(LinkTag.class),
	    					new LinkStringFilter("--pg"));
	    
	    Integer pageNumber = null;
	    
	    try {
	    	SimpleNodeIterator iterator = parser.extractAllNodesThatMatch(filter).elements ();
		    pageNumber = currentResultPageNumber;
		    Integer parsedResultPageNumber = null;
		    while(iterator.hasMoreNodes()) {
		    	TagNode nextNode = (LinkTag)iterator.nextNode();
		    	if (nextNode!=null) {
		    		parsedResultPageNumber = parseNextPageNumber(nextNode.getAttribute("href"));
			    	if (parsedResultPageNumber>pageNumber) {
			    		pageNumber = parsedResultPageNumber;
			    	}
		    	}
		    }

	    } catch(ParserException pe) {
	    	pe.printStackTrace();
	    }
		    
	    return pageNumber>currentResultPageNumber;
	}

	private Integer parseNextPageNumber(String pageNumber) {
		StringTokenizer strTok = new StringTokenizer(pageNumber,"-");
		char[] nextToken = null;
		String nextPageNumber = "";
		while(strTok.hasMoreTokens()) {
			nextToken = strTok.nextToken().toCharArray();
		}
		Integer i=2;
		if (nextToken[0]=='p' && nextToken[1]=='g') {
			while(nextToken[i]!='.') {
				nextPageNumber += nextToken[i++];
			}
			return Integer.parseInt(nextPageNumber);
		} else {
			return 1;
		}
	}

	@Override
	public ResultPageDto next() {
		// http://compare.buscape.com.ar/prod_ficha?idu=112552
	    NodeFilter filter = new AndFilter(new LinkStringFilter("www.buscape.com"),
	    					new LinkStringFilter("detalles--"));
	    
	    ResultPageDto resultPageDto = new ResultPageDto();
	    
	    ProductDto productDto = null;
	    
	    resultPageDto.setPageNumber(currentResultPageNumber);
	    
	    try {
	    	SimpleNodeIterator iterator = parser.extractAllNodesThatMatch(filter).elements();
	    	
		    while(iterator.hasMoreNodes()) {
		    	TagNode nextNode = (LinkTag)iterator.nextNode();
		    	if (nextNode!=null) {
		    		productDto = new ProductDto();
		    		productDto.setUrl(nextNode.getAttribute("href"));
		    		resultPageDto.addProduct(productDto);
		    	}
		    }
		    
		    resultPageDto.setQtyLinks(resultPageDto.getProductList().size());
		    resultPageDto.setInsertDt(Calendar.getInstance().getTime());
		    resultPageDto.setUrl(getResultPageUrl());
		    
	    } catch(ParserException pe) {
	    	pe.printStackTrace();
	    }

	    return resultPageDto;
	}

	@Override
	protected String getBaseUrl() {
		return "http://precio2.buscape.com.ar/";
	}

	@Override
	protected String getResultPageUrl() {
		if (currentResultPageNumber==1) {
			return getBaseUrl()+currentCategory+".html";
		} else {
			return getBaseUrl()+currentCategory+"--pg"+currentResultPageNumber+".html";
		}
	}

	@Override
	protected List<AttributeDto> parseProductAttributes() {
		// http://precio2.buscape.com.ar/camara-fotografica-digital-pg2.html
	    NodeFilter filter = new AndFilter(new NotFilter(new HasAttributeFilter("class","group"))
	    					,new OrFilter(new TagNameFilter("dt"),
	    					new TagNameFilter("dd")));
	    
	    List<AttributeDto> attributes = new ArrayList<AttributeDto>();
	    AttributeDto attribute = null;
	    int i=0;

	    try {
	    	SimpleNodeIterator iterator = parser.extractAllNodesThatMatch(filter).elements ();
		    while(iterator.hasMoreNodes()) {
		    	Node nextNode = (TagNode)iterator.nextNode();
		    	if (nextNode!=null) {
		    		if (i%2==0) {
		    			attribute = new AttributeDto();
		    			attribute.setAttributeName(Translate.decode(nextNode.getChildren().elementAt(0).getText()));
		    		} else {
		    			attribute.setAttributeValue(Translate.decode(nextNode.getChildren().elementAt(0).getText()));
		    			attributes.add(attribute);
		    		}
		    		i++;
		    	}
		    }
	    } catch(ParserException pe) {
	    	pe.printStackTrace();
	    }
		return attributes;
	}

	@Override
	protected String parseProductDescription() {
		return "";
	}

	@Override
	protected String parseProductTitle() {
		return "";
	}

}
