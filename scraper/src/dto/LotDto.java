package dto;

import java.util.Date;

public class LotDto {
	private Integer lotId;
	private Integer siteId;
	private String name;
	private String url;
	private Date insertDt;
	
	public LotDto() {}
	
	public Integer getLotId() {
		return lotId;
	}
	public void setLotId(Integer lotId) {
		this.lotId = lotId;
	}
	public Integer getSiteId() {
		return siteId;
	}
	public void setSiteId(Integer siteId) {
		this.siteId = siteId;
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
}
