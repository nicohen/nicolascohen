package app.bo.spider;

import java.util.List;

import api.ObjectFactory;
import api.bo.spider.ISpiderBo;
import api.dao.spider.ISpiderDao;
import dto.AttributeDto;
import dto.CategoryDto;
import dto.LotDto;
import dto.ProductDto;
import dto.ResultPageDto;
import exceptions.BusinessException;

public class SpiderBo implements ISpiderBo {

	@Override
	public Integer getNextSpideredAttributeId() throws BusinessException {
		ISpiderDao spiderDao = (ISpiderDao) ObjectFactory.getObject(ISpiderDao.class);
		try {
			return spiderDao.getNextSpideredAttributeIdSequence();
		} catch (Exception e) {
			throw new BusinessException("Error al obtener siguiente AttributeId",e);
		}
	}

	@Override
	public Integer getNextSpideredCategoryId() throws BusinessException {
		ISpiderDao spiderDao = (ISpiderDao) ObjectFactory.getObject(ISpiderDao.class);
		try {
			return spiderDao.getNextSpideredCategoryIdSequence();
		} catch (Exception e) {
			throw new BusinessException("Error al obtener siguiente CategoryId",e);
		}
	}

	@Override
	public Integer getNextSpideredLotId() throws BusinessException {
		ISpiderDao spiderDao = (ISpiderDao) ObjectFactory.getObject(ISpiderDao.class);
		try {
			return spiderDao.getNextSpideredLotIdSequence();
		} catch (Exception e) {
			throw new BusinessException("Error al obtener siguiente LotId",e);
		}
	}

	@Override
	public Integer getNextSpideredProductId() throws BusinessException {
		ISpiderDao spiderDao = (ISpiderDao) ObjectFactory.getObject(ISpiderDao.class);
		try {
			return spiderDao.getNextSpideredProductIdSequence();
		} catch (Exception e) {
			throw new BusinessException("Error al obtener siguiente ProductId",e);
		}
	}

	@Override
	public Integer getNextSpideredSiteId() throws BusinessException {
		ISpiderDao spiderDao = (ISpiderDao) ObjectFactory.getObject(ISpiderDao.class);
		try {
			return spiderDao.getNextSpideredSiteIdSequence();
		} catch (Exception e) {
			throw new BusinessException("Error al obtener siguiente SiteId",e);
		}
	}

	@Override
	public void storeAttributes(Integer spideredProductId, List<AttributeDto> attributeDtoList) throws BusinessException {
		ISpiderDao spiderDao = (ISpiderDao) ObjectFactory.getObject(ISpiderDao.class);
		try {
			spiderDao.insertAttributes(spideredProductId, attributeDtoList);
		} catch (Exception e) {
			throw new BusinessException("Error al insertar atributos para [PRODUCT_ID:"+spideredProductId+"]",e);
		}
	}

	@Override
	public Integer storeCategory(CategoryDto categoryDto) throws BusinessException {
		ISpiderDao spiderDao = (ISpiderDao) ObjectFactory.getObject(ISpiderDao.class);
		Integer categoryId = null;
		if (!existsCategory(categoryDto.getUrl())) {
			try {
				categoryId = getNextSpideredCategoryId();
				categoryDto.setCategId(categoryId);
				spiderDao.insertCategory(categoryDto);
			} catch (Exception e) {
				throw new BusinessException("Error al insertar la categoria ["+categoryDto.getName()+"]",e);
			}
		} else {
			try {
				categoryId = spiderDao.selectCategoryId(categoryDto.getUrl());
			} catch(Exception e) {
				throw new BusinessException("Error al insertar la categoria ["+categoryDto.getName()+"]",e);
			}
		}
		
		return categoryId;
	}

	@Override
	public void storeLot(LotDto lotDto) throws BusinessException {
		ISpiderDao spiderDao = (ISpiderDao) ObjectFactory.getObject(ISpiderDao.class);
		if (!existsLot(lotDto.getUrl())) {
			try {
				spiderDao.insertLot(lotDto);
			} catch (Exception e) {
				throw new BusinessException("Error al insertar el lote ["+lotDto.getName()+"]",e);
			}
		}
	}

	@Override
	public Integer storeProduct(ProductDto productDto) throws BusinessException {
		ISpiderDao spiderDao = (ISpiderDao) ObjectFactory.getObject(ISpiderDao.class);
		Integer productId = null;
		if (!existsProduct(productDto.getUrl())) {
			try {
				productId = getNextSpideredProductId();
				productDto.setProductId(productId);
				spiderDao.insertProduct(productDto);
				spiderDao.insertAttributes(productDto.getProductId(),productDto.getAttributesList());
			} catch (Exception e) {
				throw new BusinessException("Error al insertar el producto ["+productDto.getUrl()+"]",e);
			}
		} else {
			try {
				productId = spiderDao.selectProductId(productDto.getUrl());
			} catch(Exception e) {
				throw new BusinessException("Error al insertar la categoria ["+productDto.getUrl()+"]",e);
			}
		}
		
		return productId;
	}

	@Override
	public void storeResultPage(ResultPageDto resultPageDto) throws BusinessException {
		ISpiderDao spiderDao = (ISpiderDao) ObjectFactory.getObject(ISpiderDao.class);
		try {
			spiderDao.insertResultPage(resultPageDto);
		} catch(Exception e) {
			throw new BusinessException("Error al insertar la result page ["+resultPageDto.getUrl()+"]",e);
		}
	}

	@Override
	public boolean existsLot(String lotUrl) throws BusinessException {
		ISpiderDao spiderDao = (ISpiderDao) ObjectFactory.getObject(ISpiderDao.class);
		try {
			return spiderDao.existsLot(lotUrl);
		} catch(Exception e) {
			throw new BusinessException("Error verificando existencia de lote ["+lotUrl+"]",e);
		}
	}
	
	@Override
	public boolean existsCategory(String categoryUrl) throws BusinessException {
		ISpiderDao spiderDao = (ISpiderDao) ObjectFactory.getObject(ISpiderDao.class);
		try {
			return spiderDao.existsCategory(categoryUrl);
		} catch(Exception e) {
			throw new BusinessException("Error verificando existencia de categoria ["+categoryUrl+"]",e);
		}
	}

	@Override
	public boolean existsResultPage(String resultPageUrl) throws BusinessException {
		ISpiderDao spiderDao = (ISpiderDao) ObjectFactory.getObject(ISpiderDao.class);
		try {
			return spiderDao.existsResultPage(resultPageUrl);
		} catch(Exception e) {
			throw new BusinessException("Error verificando existencia de result page ["+resultPageUrl+"]",e);
		}
	}

	@Override
	public boolean existsProduct(String productUrl) throws BusinessException {
		ISpiderDao spiderDao = (ISpiderDao) ObjectFactory.getObject(ISpiderDao.class);
		try {
			return spiderDao.existsProduct(productUrl);
		} catch(Exception e) {
			throw new BusinessException("Error verificando existencia de producto ["+productUrl+"]",e);
		}
	}

}
