package dto;

public class AttributeDto {
	private Integer attributeId;
	private String attributeName;
	private String attributeValue;
	private String attributeUnit;
	
	public AttributeDto() {}
	
	public Integer getAttributeId() {
		return attributeId;
	}
	public void setAttributeId(Integer attributeId) {
		this.attributeId = attributeId;
	}
	public String getAttributeName() {
		return attributeName;
	}
	public void setAttributeName(String attributeName) {
		this.attributeName = attributeName;
	}
	public String getAttributeValue() {
		return attributeValue;
	}
	public void setAttributeValue(String attributeValue) {
		this.attributeValue = attributeValue;
	}
	public String getAttributeUnit() {
		return attributeUnit;
	}
	public void setAttributeUnit(String attributeUnit) {
		this.attributeUnit = attributeUnit;
	}
	@Override
	public String toString() {
		return "["+attributeName+": "+attributeValue+"]";
	}
}
