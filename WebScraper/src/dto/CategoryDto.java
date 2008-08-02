package dto;

import java.util.ArrayList;
import java.util.Date;
import java.util.List;

public class CategoryDto {
	private Integer categId;
	private Integer lotId;
	private String name;
	private String url;
	private Date insertDt;
	List<ResultPageDto> resultPageList = null;
	
	public CategoryDto() {
		resultPageList = new ArrayList<ResultPageDto>();
	}
	
	public Integer getCategId() {
		return categId;
	}
	public void setCategId(Integer categId) {
		this.categId = categId;
	}
	public Integer getLotId() {
		return lotId;
	}
	public void setLotId(Integer lotId) {
		this.lotId = lotId;
	}
	public String getName() {
		return name;
	}
	public void setName(String name) {
		this.name = name;
	}
	public String getUrl() {
		return url;
	}
	public void setUrl(String url) {
		this.url = url;
	}
	public Date getInsertDt() {
		return insertDt;
	}
	public void setInsertDt(Date insertDt) {
		this.insertDt = insertDt;
	}

	public List<ResultPageDto> getResultPageList() {
		return resultPageList;
	}

	public void setResultPageList(List<ResultPageDto> resultPageList) {
		this.resultPageList = resultPageList;
	}
	
	public void addResultPage(ResultPageDto resultPageDto) {
		resultPageList.add(resultPageDto);
	}
}
