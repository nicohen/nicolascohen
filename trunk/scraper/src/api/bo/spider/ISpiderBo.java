package api.bo.spider;

import java.util.List;

import dto.AttributeDto;
import dto.CategoryDto;
import dto.LotDto;
import dto.ProductDto;
import dto.ResultPageDto;
import exceptions.BusinessException;

public interface ISpiderBo {

	public Integer getNextSpideredSiteId() throws BusinessException;
	public Integer getNextSpideredLotId() throws BusinessException;
	public Integer getNextSpideredCategoryId() throws BusinessException;
	public Integer getNextSpideredProductId() throws BusinessException;
	public Integer getNextSpideredAttributeId() throws BusinessException;
	
	public void storeLot(LotDto lotDto) throws BusinessException;
	public void storeCategory(CategoryDto categoryDto) throws BusinessException;
	public void storeResultPage(ResultPageDto resultPageDto) throws BusinessException;
	public void storeProduct(ProductDto productDto) throws BusinessException;
	public void storeAttributes(Integer spideredProductId, List<AttributeDto> attributeDtoList) throws BusinessException;

	public boolean existsLot(String lotUrl) throws BusinessException;
	public boolean existsCategory(String categoryUrl) throws BusinessException;
	public boolean existsResultPage(String resultPageUrl) throws BusinessException;
	public boolean existsProduct(String productUrl) throws BusinessException;
	
}
