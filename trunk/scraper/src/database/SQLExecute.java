package database;

import java.sql.Connection;
import java.sql.SQLException;

public class SQLExecute extends AbstractSQL{

    protected boolean queryExecuted;
    protected int updateCount;

    public SQLExecute(String query){
        super(query);
    }

    public SQLExecute(Connection conn, String query){
        super(conn, query);
    }

    protected boolean executeQuery() throws SQLException{
       
        prepareQuery();
       
        if (statementType == CALLABLE){
            cs.execute();
            updateCount = cs.getUpdateCount();
        }else{
            ps.execute();
            updateCount = ps.getUpdateCount();
        }
       
        return true;
    }

    /**
     *
     * @throws SQLException
     */
    public void execute() throws SQLException{

        try{
            if (!queryExecuted){
                queryExecuted = executeQuery();
            }
        }catch(SQLException sqle){
            throw new SQLException ("Error al ejecutar la query", sqle);
        }finally{
            close();
        }

    }
   
    public void addBatch()
        throws SQLException{
       
        if(!queryPrepared) prepareQuery();
           
        if (statementType == CALLABLE){
            cs.addBatch();
        }else{
            ps.addBatch();
        }
    }
   
    public int[] executeBatch()
        throws SQLException{
           
        try{
            if (statementType == CALLABLE){
                return cs.executeBatch();
            }else{
                return ps.executeBatch();
            }
        }finally{
            close();
        }
    }
   
    public int getUpdateCount() {
        return updateCount;
    }
   
}