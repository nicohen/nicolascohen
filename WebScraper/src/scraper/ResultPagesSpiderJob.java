package scraper;

import org.htmlparser.util.ChainedException;

import scraper.sites.buscape.BuscapeResultPagesSpider;
import dto.CategoryDto;
import dto.ProductDto;
import dto.ResultPageDto;



public class ResultPagesSpiderJob {
	
	public static void main(String args[]) throws ChainedException {
		AbstractResultPagesSpider spider = new BuscapeResultPagesSpider();
		
		String categoria = "camara-fotografica-digital";
		CategoryDto categoryDto = spider.spiderCategory(categoria);
		
		Integer totalProductsQty = 0;
		Integer currentProductsQty = 0;
		Integer totalResultPagesQty = categoryDto.getResultPageList().size();
		for (ResultPageDto resultPageDto : categoryDto.getResultPageList()) {
			currentProductsQty = resultPageDto.getProductList().size();
			totalProductsQty += currentProductsQty;
			System.out.println("");
			System.out.println("["+categoria+"] - ["+currentProductsQty+" productos] - " +
					"[pagina "+resultPageDto.getPageNumber()+"/"+totalResultPagesQty+"]");
			for (ProductDto productDto : resultPageDto.getProductList()) {
				System.out.println("["+productDto.getUrl()+"]");
			}
		}
		System.out.println("Total de productos: "+totalProductsQty);
	}
	
}

