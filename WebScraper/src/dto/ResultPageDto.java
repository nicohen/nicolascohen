package dto;

import java.util.ArrayList;
import java.util.Date;
import java.util.List;

public class ResultPageDto {
	private Integer categId;
	private Integer pageNumber;
	private String url;
	private Integer qtyLinks;
	private Date insertDt;
	private List<ProductDto> productList = null;
	
	public List<ProductDto> getProductList() {
		return productList;
	}

	public void setProductList(List<ProductDto> productList) {
		this.productList = productList;
	}

	public void addProduct(ProductDto productDto) {
		productList.add(productDto);
	}
	
	public ResultPageDto() {
		productList = new ArrayList<ProductDto>();
	}
	
	public Integer getCategId() {
		return categId;
	}
	public void setCategId(Integer categId) {
		this.categId = categId;
	}
	public Integer getPageNumber() {
		return pageNumber;
	}
	public void setPageNumber(Integer pageNumber) {
		this.pageNumber = pageNumber;
	}
	public String getUrl() {
		return url;
	}
	public void setUrl(String url) {
		this.url = url;
	}
	public Integer getQtyLinks() {
		return qtyLinks;
	}
	public void setQtyLinks(Integer qtyLinks) {
		this.qtyLinks = qtyLinks;
	}
	public Date getInsertDt() {
		return insertDt;
	}
	public void setInsertDt(Date insertDt) {
		this.insertDt = insertDt;
	}
}
