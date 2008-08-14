package dto;

import java.util.ArrayList;
import java.util.Date;
import java.util.List;

public class ProductDto {
	private Integer productId;
	private Integer categId;
	private Integer pageNumber;
	private String url;
	private String title;
	private String description;
	private Date insertDt;
	private List<AttributeDto> attributesList;
	
	public ProductDto() {
		attributesList = new ArrayList<AttributeDto>();
	}

	public Integer getProductId() {
		return productId;
	}

	public void setProductId(Integer productId) {
		this.productId = productId;
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

	public String getTitle() {
		return title;
	}

	public void setTitle(String title) {
		this.title = title;
	}

	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
	}

	public Date getInsertDt() {
		return insertDt;
	}

	public void setInsertDt(Date insertDt) {
		this.insertDt = insertDt;
	}

	public List<AttributeDto> getAttributesList() {
		return attributesList;
	}

	public void setAttributesList(List<AttributeDto> attributesList) {
		this.attributesList = attributesList;
	}
	
	public void addAttribute(AttributeDto attributeDto) {
		this.attributesList.add(attributeDto);
	}
	
}
