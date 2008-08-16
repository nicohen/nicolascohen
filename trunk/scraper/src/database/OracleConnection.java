package database;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class OracleConnection {
    
	public static Connection getConnection() {
		
	    Connection connection = null;
	    try {
	        // Load the JDBC driver
	        String driverName = "oracle.jdbc.driver.OracleDriver";
	        Class.forName(driverName);
	    
	        // Create a connection to the database
	        String serverName = "127.0.0.1";
	        String portNumber = "1521"; //no deberia ser 8095?
	        String sid = "XE";
	        String url = "jdbc:oracle:thin:@" + serverName + ":" + portNumber + ":" + sid;
	        String username = "system";
	        String password = "p5f7sa7h";
	        
	        connection = DriverManager.getConnection(url, username, password);
	    } catch (ClassNotFoundException e) {
	        // Could not find the database driver
	    } catch (SQLException e) {
	        // Could not connect to the database
	    }
	    
	    return connection;
	}
}
