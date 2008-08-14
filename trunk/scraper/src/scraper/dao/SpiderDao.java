package scraper.dao;

import java.sql.SQLException;

import database.SQLCursor;
import database.SQLExecute;
import dto.ProductDto;
import dto.ResultPageDto;

public class SpiderDao {

//	Connection conexion = null;
//	
//	public SpiderDao() {
//		conexion = MySQLConnection.getConnection();
//	}

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

	public void storeResultPage(ResultPageDto resultPage) {
		for(ProductDto productDto : resultPage.getProductList()) {
			storeProduct(productDto);
		}
	}

	public void storeProduct(ProductDto productDto) {
		SQLExecute sqlE = null;
		
		try {
			sqlE = new SQLExecute("insert into ");
		} finally {
			
		}
	}
}
