package app.dao.spider;

import java.sql.SQLException;
import java.util.List;

import api.dao.spider.ISpiderDao;
import database.SQLCursor;
import database.SQLExecute;
import dto.AttributeDto;
import dto.CategoryDto;
import dto.LotDto;
import dto.ProductDto;
import dto.ResultPageDto;
import exceptions.DataAccessException;

public class SpiderDao implements ISpiderDao {

	public Integer getNextCategId() throws SQLException {
		SQLCursor sql = null;
		
		try {
			sql = new SQLCursor("select nombre,edad from prueba where nombre='?'");
			sql.setString(1, "Nicolas");
			if (sql.next()) {
				System.out.println(sql.getString("nombre")+" "+sql.getInt("edad"));
			}
		} finally {
            sql.close();
		}
		return null;
		
	}

	@Override
	public Integer getNextSpideredAttributeIdSequence() throws DataAccessException {
		SQLCursor sql = null;
		
		try {
			sql = new SQLCursor("select spidered_attributes_seq.nextval siguiente from dual");
			sql.next();
			return sql.getInt("siguiente");
		} catch(Exception e) {
			throw new DataAccessException("Error al intentar obtener nextval en secuencia spidered_attributes_seq",e);
		} finally {
			sql.close();
		}
	}

	@Override
	public Integer getNextSpideredCategoryIdSequence() throws DataAccessException {
		SQLCursor sql = null;
		
		try {
			sql = new SQLCursor("select spidered_categories_seq.nextval siguiente from dual");
			sql.next();
			return sql.getInt("siguiente");
		} catch(Exception e) {
			throw new DataAccessException("Error al intentar obtener nextval en secuencia spidered_categories_seq",e);
		} finally {
			sql.close();
		}
	}

	@Override
	public Integer getNextSpideredLotIdSequence() throws DataAccessException {
		SQLCursor sql = null;
		
		try {
			sql = new SQLCursor("select spidered_lots_seq.nextval siguiente from dual");
			sql.next();
			return sql.getInt("siguiente");
		} catch(Exception e) {
			throw new DataAccessException("Error al intentar obtener nextval en secuencia spidered_lots_seq",e);
		} finally {
			sql.close();
		}
	}

	@Override
	public Integer getNextSpideredProductIdSequence() throws DataAccessException {
		SQLCursor sql = null;
		
		try {
			sql = new SQLCursor("select spidered_products_seq.nextval siguiente from dual");
			sql.next();
			return sql.getInt("siguiente");
		} catch(Exception e) {
			throw new DataAccessException("Error al intentar obtener nextval en secuencia spidered_products_seq",e);
		} finally {
			sql.close();
		}
	}

	@Override
	public Integer getNextSpideredSiteIdSequence() throws DataAccessException {
		SQLCursor sql = null;
		
		try {
			sql = new SQLCursor("select spidered_sites_seq.nextval siguiente from dual");
			sql.next();
			return sql.getInt("siguiente");
		} catch(Exception e) {
			throw new DataAccessException("Error al intentar obtener nextval en secuencia spidered_sites_seq",e);
		} finally {
			sql.close();
		}
	}

	@Override
	public void insertAttributes(Integer spideredProductId, List<AttributeDto> attributeDtoList) throws DataAccessException {
		SQLExecute sqlE = null;
		
		try {
			for (AttributeDto attributeDto : attributeDtoList) {
				sqlE = new SQLExecute("insert into spidered_attributes " +
						"(attr_id,product_id,name,value,unit,insert_dt) " +
						" values("+attributeDto.getAttributeId()+","+spideredProductId+"," +
						""+attributeDto.getAttributeName()+","+attributeDto.getAttributeValue()+"," +
						""+attributeDto.getAttributeUnit()+",sysdate)");
				sqlE.execute();
			}
		} catch(Exception e) {
			throw new DataAccessException("Error al intentar insertar atributos para [PRODUCT_ID:"+spideredProductId+"]",e);
		} finally {
			sqlE.close();
		}
		
	}

	@Override
	public void insertCategory(CategoryDto categoryDto) throws DataAccessException {
		SQLExecute sqlE = null;
		
		try {
			sqlE = new SQLExecute("insert into spidered_categories " +
					"(categ_id,lot_id,name,url,insert_dt) " +
					" values("+categoryDto.getCategId()+","+categoryDto.getLotId()+"," +
					""+categoryDto.getName()+","+categoryDto.getUrl()+",sysdate)");
			sqlE.execute();
		} catch(Exception e) {
			throw new DataAccessException("Error al intentar insertar la categoria ["+categoryDto.getName()+"]",e);
		} finally {
			sqlE.close();
		}
		
	}

	@Override
	public void insertLot(LotDto lotDto) throws DataAccessException {
		SQLExecute sqlE = null;
		
		try {
			sqlE = new SQLExecute("insert into spidered_lots " +
					"(lot_id,site_id,name,url,insert_dt) " +
					" values("+lotDto.getLotId()+","+lotDto.getSiteId()+"," +
					""+lotDto.getName()+","+lotDto.getUrl()+",sysdate)");
			sqlE.execute();
		} catch(Exception e) {
			throw new DataAccessException("Error al intentar insertar el lote ["+lotDto.getName()+"]",e);
		} finally {
			sqlE.close();
		}
	}

	@Override
	public void insertProduct(ProductDto productDto) throws DataAccessException {
		SQLExecute sqlE = null;
		
		try {
			sqlE = new SQLExecute("insert into spidered_products " +
					"(product_id,categ_id,page_number,url,title,description,insert_dt) " +
					" values("+productDto.getProductId()+","+productDto.getCategId()+"," +
					""+productDto.getPageNumber()+","+productDto.getUrl()+","+productDto.getTitle()+"," +
					""+productDto.getDescription()+",sysdate)");
			sqlE.execute();
		} catch(Exception e) {
			throw new DataAccessException("Error al intentar insertar el producto ["+productDto.getUrl()+"]",e);
		} finally {
			sqlE.close();
		}
	}

	@Override
	public void insertResultPage(ResultPageDto resultPageDto) throws DataAccessException {
		SQLExecute sqlE = null;
		
		try {
			sqlE = new SQLExecute("insert into spidered_result_pages " +
					"(categ_id,page_number,url,qty_links,insert_dt) " +
					" values("+resultPageDto.getCategId()+","+resultPageDto.getPageNumber()+"," +
					""+resultPageDto.getUrl()+","+resultPageDto.getQtyLinks()+",sysdate)");
			sqlE.execute();
		} catch(Exception e) {
			throw new DataAccessException("Error al intentar insertar la result page ["+resultPageDto.getUrl()+"]",e);
		} finally {
			sqlE.close();
		}
	}

	@Override
	public boolean existsCategory(String categoryUrl) throws DataAccessException {
		SQLCursor sql = null;
		
		try {
			sql = new SQLCursor("select 1 from spidered_categories where url = "+categoryUrl);
			return sql.next();
		} catch (Exception e) {
			throw new DataAccessException("Error verificando existencia de categoria para ["+categoryUrl+"]",e);
		} finally {
			sql.close();
		}
	}

	@Override
	public boolean existsLot(String lotUrl) throws DataAccessException {
		SQLCursor sql = null;
		
		try {
			sql = new SQLCursor("select 1 from spidered_lots where url = "+lotUrl);
			return sql.next();
		} catch (Exception e) {
			throw new DataAccessException("Error verificando existencia de lote para ["+lotUrl+"]",e);
		} finally {
			sql.close();
		}
	}

	@Override
	public boolean existsProduct(String productUrl) throws DataAccessException {
		SQLCursor sql = null;
		
		try {
			sql = new SQLCursor("select 1 from spidered_products where url = "+productUrl);
			return sql.next();
		} catch (Exception e) {
			throw new DataAccessException("Error verificando existencia de producto para ["+productUrl+"]",e);
		} finally {
			sql.close();
		}
	}

	@Override
	public boolean existsResultPage(String resultPageUrl) throws DataAccessException {
		SQLCursor sql = null;
		
		try {
			sql = new SQLCursor("select 1 from spidered_result_pages where url = "+resultPageUrl);
			return sql.next();
		} catch (Exception e) {
			throw new DataAccessException("Error verificando existencia de result page para ["+resultPageUrl+"]",e);
		} finally {
			sql.close();
		}
	}

	@Override
	public Integer selectCategoryId(String url) throws DataAccessException {
		SQLCursor sql = null;
		
		try {
			sql = new SQLCursor("select categ_id from spidered_categories where url = "+url);
			if (sql.next()) {
				return sql.getInt("categ_id");
			}
			return null;
		} catch (Exception e) {
			throw new DataAccessException("Error obteniendo categ_id para ["+url+"]",e);
		} finally {
			sql.close();
		}
	}

	@Override
	public Integer selectProductId(String url) throws DataAccessException {
		SQLCursor sql = null;
		
		try {
			sql = new SQLCursor("select categ_id from spidered_products where url = "+url);
			if (sql.next()) {
				return sql.getInt("product_id");
			}
			return null;
		} catch (Exception e) {
			throw new DataAccessException("Error obteniendo product_id para ["+url+"]",e);
		} finally {
			sql.close();
		}
	}

}
