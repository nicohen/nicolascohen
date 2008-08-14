import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.htmlparser.util.ChainedException;

import scraper.AbstractResultPagesSpider;
import scraper.sites.buscape.BuscapeResultPagesSpider;
import dto.CategoryDto;
import dto.ProductDto;
import dto.ResultPageDto;

public class HelloWorld extends ControllerServlet {

	private static final long serialVersionUID = 5132936917759337060L;

	@Override
	protected String doContent(HttpServletRequest request,HttpServletResponse response) throws ChainedException {
		AbstractResultPagesSpider spider = new BuscapeResultPagesSpider();
		
		StringBuffer strBuf = new StringBuffer();
		String categoria = "camara-fotografica-digital";
		
		CategoryDto categoryDto = null;
		categoryDto = spider.spiderCategory(categoria);

		Integer totalProductsQty = 0;
		Integer currentProductsQty = 0;
		Integer totalResultPagesQty = categoryDto.getResultPageList().size();
		for (ResultPageDto resultPageDto : categoryDto.getResultPageList()) {
			currentProductsQty = resultPageDto.getProductList().size();
			totalProductsQty += currentProductsQty;
			strBuf.append("<br>");
			strBuf.append("["+categoria+"] - ["+currentProductsQty+" productos] - " +
					"[pagina "+resultPageDto.getPageNumber()+"/"+totalResultPagesQty+"]");
			for (ProductDto productDto : resultPageDto.getProductList()) {
				strBuf.append("<br>");
				strBuf.append("["+productDto.getUrl()+"]");
			}
		}
		strBuf.append("<br>Total de productos: "+totalProductsQty);
		
		return strBuf.toString();
	}
}