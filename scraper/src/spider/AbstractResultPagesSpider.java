package spider;

import java.util.ArrayList;
import java.util.Calendar;
import java.util.List;

import org.htmlparser.Parser;
import org.htmlparser.util.ChainedException;
import org.htmlparser.util.ParserException;

import spider.utils.HtmlUtils;
import dto.AttributeDto;
import dto.CategoryDto;
import dto.ProductDto;
import dto.ResultPageDto;

public abstract class AbstractResultPagesSpider implements AbstractResultPagesIterator {
	
	protected Parser  parser = null;
	protected String  nextHtml = null;
	protected String  currentCategory = null;
	protected Integer currentResultPageNumber = null;
//	private SpiderDao spiderDao;
	
	public AbstractResultPagesSpider() {
		parser = new Parser();
		currentResultPageNumber = 0;
	}
	
	public CategoryDto spiderCategory(String category) throws ChainedException {
		currentCategory = category;
		CategoryDto categoryDto = new CategoryDto();
		List<ProductDto> newProductsList = null;
		ResultPageDto resultPage = null;
		
		getNextResultPageHtml();
		while(hasNext()) {
			System.out.println("--- Analizando result page "+currentResultPageNumber+" ---");
			parser.reset();
			resultPage = next();
			newProductsList = new ArrayList<ProductDto>();
			for (ProductDto productDto : resultPage.getProductList()) {
				getNextProductHtml(productDto.getUrl());
	    		productDto.setInsertDt(Calendar.getInstance().getTime());
	    		productDto.setPageNumber(currentResultPageNumber);
				parser.reset();
	    		productDto.setTitle(parseProductTitle());
				parser.reset();
	    		productDto.setDescription(parseProductDescription());
				parser.reset();
	    		productDto.setAttributesList(parseProductAttributes());
				newProductsList.add(productDto);
			}
			resultPage.setProductList(newProductsList);
			categoryDto.addResultPage(resultPage);
			
//			spiderDao.storeResultPage(resultPage);
			
			getNextResultPageHtml();
		}
		
		return categoryDto;
	}

	private void getNextResultPageHtml() throws ChainedException {
		currentResultPageNumber++;
		nextHtml = HtmlUtils.getHtml(getResultPageUrl());
		try {
			parser.setInputHTML(nextHtml);
			parser.reset();
		} catch(ParserException pe) {
			throw new ChainedException();
		}
	}	
	
	private void getNextProductHtml(String url) throws ChainedException {
		parser.reset();
		nextHtml = HtmlUtils.getHtml(url);
		try {
			parser.setInputHTML(nextHtml);
		} catch(ParserException pe) {
			throw new ChainedException();
		}
	}

	@Override
	public void remove() {}
	
	/**
	 * Devuelve el base url de la pagina que se scrapee
	 * Ejemplo: http://precio2.buscape.com.ar/
	 * @return
	 */
	protected abstract String getBaseUrl();
	
	/**
	 * Devuelve la url de la siguiente pagin a scrapear
	 * @return
	 */
	protected abstract String getResultPageUrl();
	
	/**
	 * Este metodo devuelve el titulo del producto
	 * @return
	 */
	protected abstract String parseProductTitle();
	
	/**
	 * Este metodo devuelve la descripcion del producto
	 * @return
	 */
	protected abstract String parseProductDescription();
	
	/**
	 * Este metodo devuelve todos los atributos del producto
	 * @return
	 */
	protected abstract List<AttributeDto> parseProductAttributes();
	
}
