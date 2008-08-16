package app.dao.spider;

import java.sql.SQLException;

import database.SQLCursor;

public class SpiderDao {
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

}
