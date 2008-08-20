package api.dao.spider;

import java.util.List;

import dto.AttributeDto;
import dto.CategoryDto;
import dto.LotDto;
import dto.ProductDto;
import dto.ResultPageDto;
import exceptions.DataAccessException;

public interface ISpiderDao {
	
	public Integer getNextSpideredSiteIdSequence() throws DataAccessException;
	public Integer getNextSpideredLotIdSequence() throws DataAccessException;
	public Integer getNextSpideredCategoryIdSequence() throws DataAccessException;
	public Integer getNextSpideredProductIdSequence() throws DataAccessException;
	public Integer getNextSpideredAttributeIdSequence() throws DataAccessException;
	
	public void insertLot(LotDto lotDto) throws DataAccessException;
	public void insertCategory(CategoryDto categoryDto) throws DataAccessException;
	public void insertResultPage(ResultPageDto resultPageDto) throws DataAccessException;
	public void insertProduct(ProductDto productDto) throws DataAccessException;
	public void insertAttributes(Integer spideredProductId, List<AttributeDto> attributeDtoList) throws DataAccessException;
	
	public boolean existsLot(String lotUrl) throws DataAccessException;
	public boolean existsCategory(String categoryUrl) throws DataAccessException;
	public boolean existsResultPage(String resultPageUrl) throws DataAccessException;
	public boolean existsProduct(String productUrl) throws DataAccessException;
	
	public Integer selectCategoryId(String url) throws DataAccessException;
	public Integer selectProductId(String url) throws DataAccessException;

}
