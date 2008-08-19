package spider.jobs;

import spider.AbstractResultPagesSpider;
import spider.sites.buscape.BuscapeResultPagesSpider;
import dto.CategoryDto;
import dto.ProductDto;
import dto.ResultPageDto;
import exceptions.BusinessException;
import exceptions.spider.SpiderException;



public class ResultPagesSpiderJob {
	
	public static void main(String args[]) throws BusinessException, SpiderException {
		AbstractResultPagesSpider spider = new BuscapeResultPagesSpider();
		
		
		String categoria = "filmadora";
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

