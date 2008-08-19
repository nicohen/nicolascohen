package spider;

import java.util.ArrayList;
import java.util.Calendar;
import java.util.List;

import org.htmlparser.Parser;
import org.htmlparser.util.ParserException;

import spider.utils.HtmlUtils;
import api.ObjectFactory;
import api.bo.spider.ISpiderBo;
import dto.AttributeDto;
import dto.CategoryDto;
import dto.ProductDto;
import dto.ResultPageDto;
import exceptions.BusinessException;
import exceptions.spider.SpiderException;

public abstract class AbstractResultPagesSpider implements AbstractResultPagesIterator {
	
	protected Parser  parser = null;
	protected String  nextHtml = null;
	protected String  currentCategory = null;
	protected Integer currentResultPageNumber = null;
	private ISpiderBo spiderBo;
	
	public AbstractResultPagesSpider() {
		parser = new Parser();
		currentResultPageNumber = 0;
		spiderBo = (ISpiderBo) ObjectFactory.getObject(ISpiderBo.class);
	}
	
	public CategoryDto spiderCategory(String category) throws BusinessException, SpiderException {
		currentCategory = category;
		CategoryDto categoryDto = new CategoryDto();
		List<ProductDto> newProductsList = null;
		ResultPageDto resultPage = null;
		
		getNextResultPageHtml();
		while(hasNext()) {
			System.out.println("\n--- Analizando result page "+currentResultPageNumber+" ---");
			parser.reset();
			resultPage = next();
			newProductsList = new ArrayList<ProductDto>();
			int i=1;
			for (ProductDto productDto : resultPage.getProductList()) {
				
				System.out.println(">>> # "+(i++)+" # "+productDto.getUrl());
				
				//Parseo el producto
				getNextProductHtml(productDto.getUrl());
	    		productDto.setInsertDt(Calendar.getInstance().getTime());
	    		productDto.setPageNumber(currentResultPageNumber);
				parser.reset();
	    		productDto.setTitle(parseProductTitle());
				parser.reset();
	    		productDto.setDescription(parseProductDescription());
				parser.reset();
	    		productDto.setAttributesList(parseProductAttributes());
				
	    		System.out.println(">>> "+productDto.getAttributesList().toString());
				
	    		newProductsList.add(productDto);
	    		
	    		//Almaceno el producto parseado
	    		spiderBo.storeProduct(productDto);
			}
			
			resultPage.setProductList(newProductsList);
			categoryDto.addResultPage(resultPage);
			
			spiderBo.storeResultPage(resultPage);
			
			getNextResultPageHtml();
		}
		
		return categoryDto;
	}

	private void getNextResultPageHtml() throws SpiderException {
		currentResultPageNumber++;
		nextHtml = HtmlUtils.getHtml(getResultPageUrl());
		try {
			parser.setInputHTML(nextHtml);
			parser.reset();
		} catch(ParserException pe) {
			throw new SpiderException("",pe);
		}
	}	
	
	private void getNextProductHtml(String url) throws SpiderException {
		parser.reset();
		nextHtml = HtmlUtils.getHtml(url);
		try {
			parser.setInputHTML(nextHtml);
		} catch(ParserException pe) {
			throw new SpiderException("",pe);
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
