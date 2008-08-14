package database;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class MySQLConnection {
	
	public static Connection getConnection() {
		
		Connection conn = null;
		
	    try {
	        Class.forName("com.mysql.jdbc.Driver").newInstance();
	    } catch (Exception ex) {
	        // handle the error
	    }
		
		try {
		    conn = DriverManager.getConnection("jdbc:mysql://localhost/catalogo?user=root");
		} catch (SQLException ex) {
		    System.out.println("SQLException: " + ex.getMessage());
		    System.out.println("SQLState: " + ex.getSQLState());
		    System.out.println("VendorError: " + ex.getErrorCode());
		}
		return conn;
	}

}
