package database;

import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;

import com.mysql.jdbc.Statement;

public class SQLCursor extends AbstractSQL {

    private static final int DEFAULT_FETCH_SIZE = 10;
    protected boolean queryExecuted;
    private int fetchSize = DEFAULT_FETCH_SIZE;

    public SQLCursor(Connection conn, String query) {
        super(conn, query);
    }

    public SQLCursor(String query) {
        super(query);
    }
    
    protected void executeQuery() throws SQLException {
        try {
            prepareQuery();

            if (this.statementType == CALLABLE) {
                this.cs.execute();
                this.rs = (ResultSet) this.cs.getObject(1);
            } else {
                this.rs = this.ps.executeQuery();
            }

            this.rs.setFetchSize(this.fetchSize);
            this.queryExecuted = true;
        } catch (RuntimeException e) {
            doException("Error en excecuteQuery() query["+query+"]", e);
        }
    }
	
    public boolean next() throws SQLException {
        try {
            try {

                if (!this.queryExecuted) {
                    executeQuery();
                }

                if (this.rs.next()) {
                    return true;
                } else {
                    close();
                    return false;
                }
            } catch (SQLException sqle) {
                close();
                throw new SQLException("Error en next:["+this.query+"]", sqle);
            }
        } catch (RuntimeException re) {
            doException("en next() query["+query+"]", re);
            return false;
        }
    }
	
	public ResultSet getSQLCursor(Connection conn, String query) throws SQLException {
		Statement stmt = null;
		ResultSet rs = null;

		try {
		    stmt = (Statement) conn.createStatement();
		    rs = stmt.executeQuery(query);

		    if (stmt.execute(query)) {
		        rs = stmt.getResultSet();
		    }
		    
		    return rs;
		    
		} catch(SQLException e) {
			throw new SQLException("Error en query ["+query+"]");
		}
	}
	
    public String getString(String column) throws SQLException {
        try {
            return this.rs.getString(column);
        } catch (RuntimeException e) {
            doException("en getString() query["+query+"]", e);
            return null;
        }
    }

    public Integer getInt(String column) throws SQLException {
        try {
            return this.rs.getInt(column);
        } catch (RuntimeException e) {
            doException("en getString() query["+query+"]", e);
            return null;
        }
    }

}
