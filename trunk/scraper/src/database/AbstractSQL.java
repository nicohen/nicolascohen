package database;

import java.math.BigDecimal;
import java.sql.CallableStatement;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.sql.Timestamp;
import java.sql.Types;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.List;

public abstract class AbstractSQL {
    /**
     * El query es un procedure o función precompilda en la RDBMS
     */
    public static final int CALLABLE = 0;

    /**
     * El query es generado dinamicamente desde Java
     */
    public static final int PREPARED = 1;

    /**
     * Tipo de query
     *
     * @see CALLABLE
     * @see PREPARED
     */
    int statementType;

    /**
     * El query a ejectuar
     */
    String query;

    /**
     * Conexión física a la RDBMS
     */
    Connection conn;

    /**
     * Query pre-compilado en la RDBMS a ser ejecutado
     */
    CallableStatement cs;

    /**
     * Query a ser ejectuado, generado dinamicamente desde Java
     */
    PreparedStatement ps;

    ResultSet rs;

    boolean resourcesClosed;

    ArrayList<Object> parameters = new ArrayList<Object>(10);

    protected boolean queryPrepared;

    /**
     * @param pool
     *            Pool de conexiones a la RDBMS sobre la que deseamos realizar
     *            el query
     * @param query
     *            Query a realizar
     */
    public AbstractSQL(String query) {
        try {
            initialize(null, query);
        } catch (RuntimeException e) {
            doException("error en AbstractSQL()", e);
        }
    }

    /**
     * @param conn
     *            Conexión a la RDBMS sobre la que deseamos realizar el query
     * @param query
     *            Query a realizar
     */
    public AbstractSQL(Connection conn, String query) {
        try {
            initialize(conn, query);
        } catch (RuntimeException e) {
            doException("error en AbstractSQL()", e);
        }
    }

    private void initialize(Connection conn, String query) {
        this.query = query;
        this.conn = conn;
        try {

            if (query != null && query.length() > 0
                    && query.substring(0, 1).equals("{")) {
                statementType = CALLABLE;
            } else {
                statementType = PREPARED;
            }
        } catch (RuntimeException e) {
            doException("error en initialize()", e);
        }
    }

    /**
     *
     * @return @throws
     *         SQLException
     */
    void prepareQuery() throws SQLException {
        try {
            if (conn == null)
                //conn = MySQLConnection.getConnection();
            	conn = OracleConnection.getConnection();

            if (statementType == CALLABLE) {
                cs = conn.prepareCall(query);
            } else {
                ps = conn.prepareStatement(query);
            }

//            prepareParameters();

            queryPrepared = true;
        } catch (RuntimeException e) {
            doException("error en prepareQuery()", e);
        }
    }

//    /**
//     * <p>
//     * Retorna la query armada con todos los parametros seteados. Se utiliza
//     * para poder saber la query q se armo.
//     * </p>
//     *
//     * @author Matias Rege 02/03/2007 - 09:54:16
//     * @return
//     */
//    public String getQuery() {
//        String toDate = "to_date('?', 'DD-MM-YYYY HH24:MI:SS')";
//        SimpleDateFormat formater = new SimpleDateFormat("dd-MM-yyyy HH:mm:ss");
//        StringBuffer buf = new StringBuffer(query);
//
//        for (int j = 0; j < parameters.size(); j++) {
//            Object obj = parameters.get(j);
//           
//            if (obj instanceof Long || obj instanceof Integer){
//                LibTxt.replace(buf, "?", obj.toString());               
//                continue;
//            }
//           
//            if (obj instanceof String){
//                LibTxt.replace(buf, "?", "'" + obj.toString() + "'");               
//                continue;
//            }
//   
//            if (obj instanceof Calendar){
//                Calendar fecha = (Calendar) obj;
//                String fechaToDate = LibTxt.replace(toDate, "?", formater.format(fecha.getTime()));
//                LibTxt.replace(buf, "?", fechaToDate);               
//                continue;           
//            }
//           
//            if (obj instanceof Date){
//                Date fecha = (Date) obj;
//                String fechaToDate = LibTxt.replace(toDate, "?", formater.format(fecha));
//                LibTxt.replace(buf, "?", fechaToDate);               
//                continue;           
//            }
//           
//            LibTxt.replace(buf, "?", obj == null ? "null":obj.toString());           
//        }
//       
//        return buf.toString();
//    }

    /**
     * Cierra el cursor si no se lo recorrio completamente. En el caso en que un
     * cursor se recorra completamente, no es necesario llamar a
     * <code>close()</code>
     */
    public void close() {
        try {
            if (!resourcesClosed) {

                try {
                    try {
                        if (rs != null)
                            rs.close();
                        if (statementType == CALLABLE) {
                            if (cs != null)
                                cs.close();
                            cs = null;
                        } else {
                            if (ps != null)
                                ps.close();
                            ps = null;
                        }
                    } catch (SQLException sqle2) {
                    }
                    conn = null;
                } finally {
                    resourcesClosed = true;
                }
            }
        } catch (RuntimeException e) {
            doException("error en close()", e);
        }
    }

    @SuppressWarnings({"unchecked"})
    private void checkPos(int pos) {
        try {
            if (parameters.size() < pos) {
                for (int j = parameters.size(); j < pos; j++) {
                    parameters.add(j, new NoParam());
                }
            }
        } catch (RuntimeException e) {
            doException("error en checkPos()", e);
        }
    }

    /*
     * (non-Javadoc)
     *
     * @see java.lang.Object#finalize()
     */
    protected void finalize() throws Throwable {
        try {
            if (!resourcesClosed) {
                close();
            }
        } catch (RuntimeException e) {
            doException("error en finalize()", e);
        }
    }

//    /**
//     *
//     * @param pos
//     * @param param
//     */
//    @SuppressWarnings("unchecked")
//    public void setClob(int pos, String param) {
//        try {
//            clobsExist = true;
//            checkPos(pos);
//            parameters.set(pos - 1, new MLClob(param));
//        } catch (RuntimeException e) {
//            doException("error en setClob()", e);
//        }
//    }

    /**
     * Setea un parámetro de tipo String
     *
     * @param pos
     *            Posición del parámetro
     * @param param
     *            Posición del parámetro
     */
    @SuppressWarnings("unchecked")
    public void setString(int pos, String param) {
        try {
            checkPos(pos);
            if (param != null) {
                parameters.set(pos - 1, param);
            } else {
                setNull(pos, Types.VARCHAR);
            }
        } catch (RuntimeException e) {
            doException("error en setString()", e);
        }
    }

    /**
     * Setea un parámetro de tipo int
     *
     * @param pos
     *            Posición del parámetro
     * @param param
     *            Posición del parámetro
     */
    @SuppressWarnings("unchecked")
    public void setInt(int pos, int param) {
        try {
            checkPos(pos);
            parameters.set(pos - 1, new Integer(param));
        } catch (RuntimeException e) {
            doException("error en setInt()", e);
        }
    }
   
    /**
     * Setea un parámetro de tipo Integer
     *
     * @param pos
     *            Posición del parámetro
     * @param param
     *            Posición del parámetro
     */
    @SuppressWarnings("unchecked")
    public void setInt(int pos, Integer param) {
        try {
            checkPos(pos);
            if (param != null) {
                this.parameters.set(pos - 1, param);
            } else {
                setNull(pos, Types.INTEGER);
            }
        } catch (RuntimeException e) {
            doException("error en setInt()", e);
        }
    }

    /**
     * Setea un parámetro de tipo Date
     *
     * @param pos
     *            Posición del parámetro
     * @param param
     *            Posición del parámetro
     */
    @SuppressWarnings("unchecked")
    public void setDate(int pos, java.util.Date param) {
        try {
            checkPos(pos);
            if (param != null) {
                parameters.set(pos - 1, new java.sql.Date(param.getTime()));
            } else {
                setNull(pos, Types.DATE);
            }
        } catch (RuntimeException e) {
            doException("error en setDate()", e);
        }
    }

    /**
     * Setea un parámetro de tipo Calendar
     *
     * @param pos
     *            Posición del parámetro
     * @param param
     *            Posición del parámetro
     *           
     * @author Nicolás Allegrotti - Mar 15, 2007
     */
    @SuppressWarnings("unchecked")
    public void setCalendar(int pos, Calendar param) {
        try {
            checkPos(pos);
//	            if (param != null) {
//	                parameters.set(pos - 1, new java.sql.Date(param.getTimeInMillis()));
//	            } else {
//	                setNull(pos, Types.DATE);
//	            }
            if (param != null) {
                parameters.set(pos - 1, new Timestamp(param.getTimeInMillis()));
            } else {
                setNull(pos, Types.TIMESTAMP);
            }
        } catch (RuntimeException e) {
            doException("error en setCalendar()", e);
        }
    }

    /**
     * Setea un parámetro de tipo Date
     *
     * @param pos
     *            Posición del parámetro
     * @param param
     *            Posición del parámetro
     */
    @SuppressWarnings("unchecked")
    public void setTimestamp(int pos, Timestamp param) {
        try {
            checkPos(pos);
            if (param != null) {
                parameters.set(pos - 1, param);
            } else {
                setNull(pos, Types.TIMESTAMP);
            }
        } catch (RuntimeException e) {
            doException("error en setTimestamp()", e);
        }
    }

    /**
     * Setea un parametro de tipo java.util.Date y no te trunca horas, minutos y
     * segundos.
     *
     * @author gcalvi
     */
    @SuppressWarnings("unchecked")
    public void setTimestamp(int pos, java.util.Date param) {
        try {
            checkPos(pos);
            if (param != null) {
                parameters.set(pos - 1, new Timestamp(param.getTime()));
            } else {
                setNull(pos, Types.TIMESTAMP);
            }
        } catch (RuntimeException e) {
            doException("error en setTimestamp()", e);
        }
    }

    @SuppressWarnings("unchecked")
    public void setDouble(int pos, double param) {
        try {
            checkPos(pos);
            parameters.set(pos - 1, new Double(param));
        } catch (RuntimeException e) {
            doException("error en setDouble()", e);
        }
    }
   
    /**
     * Setea un parámetro de tipo Double
     *
     * @param pos
     *            Posición del parámetro
     * @param param
     *            Posición del parámetro
     */
    @SuppressWarnings("unchecked")
    public void setDouble(int pos, Double param) {
        try {
            checkPos(pos);
            if (param != null) {
                this.parameters.set(pos - 1, param);
            } else {
                setNull(pos, Types.DOUBLE);
            }
        } catch (RuntimeException e) {
            doException("error en setDouble()", e);
        }
    }

    /**
     * Setea un parámetro de tipo BigDecimal
     *
     * @param pos
     *            Posición del parámetro
     * @param param
     *            Posición del parámetro
     * @author Nicolás Allegrotti - Nov 9, 2006
     */
    @SuppressWarnings({ "unchecked" })
    public void setBigDecimal(int pos, BigDecimal param) {
        try {
            checkPos(pos);
            if (param != null) {
                this.parameters.set(pos - 1, param);
            } else {
                setNull(pos, Types.DECIMAL);
            }
        } catch (RuntimeException e) {
            doException("error en setBigDecimal("+
                        pos + ", "+ param.toString() + ")", e);
        }
    }
   
/** 
 * Se quitó porque la implementación que recibe Long tambien soporta long
 *
 * @author Nicolás Allegrotti - Feb 20, 2007
 */
    @SuppressWarnings("unchecked")
    public void setLong(int pos, long param) {
        try {
            checkPos(pos);
            parameters.set(pos - 1, new Long(param));
        } catch (RuntimeException e) {
            doException("error en setLong()", e);
        }
    }

    /**
     *
     */
    @SuppressWarnings("unchecked")
    public void setLong(int pos, Long param) {
        try {
            checkPos(pos);
            if (param != null) {
                this.parameters.set(pos - 1, param);
            } else {
                setNull(pos, Types.NUMERIC);
            }
        } catch (RuntimeException e) {
            doException("error en setLong()", e);
        }
    }

    @SuppressWarnings("unchecked")
    public void setFloat(int pos, float param) {
        try {
            checkPos(pos);
            parameters.set(pos - 1, new Float(param));
        } catch (RuntimeException e) {
            doException("error en setFloat()", e);
        }
    }
   
    /**
     * Asigna una variable de tipo Float
     *
     * @param pos
     * @param param
     * @author Nicolás Allegrotti - Feb 20, 2007
     */
    @SuppressWarnings("unchecked")
    public void setFloat(int pos, Float param) {
        try {
            checkPos(pos);
            if (param != null) {
                this.parameters.set(pos - 1, param);
            } else {
                setNull(pos, Types.FLOAT);
            }
        } catch (RuntimeException e) {
            doException("error en setFloat()", e);
        }
    }

    @SuppressWarnings("unchecked")
    public void setNull(int pos, int type) {
        try {
            checkPos(pos);
            parameters.set(pos - 1, new MLNull(type));
        } catch (RuntimeException e) {
            doException("error en setNull()", e);
        }
    }

//    /**
//     * Setea un parámetro de tipo BinaryStream
//     *
//     * @param pos
//     *            Posición del parámetro
//     * @param param
//     *            Posición del parámetro
//     */
//    @SuppressWarnings("unchecked")
//    public void setBinaryStream(int pos, InputStream param, int size) {
//        try {
//            checkPos(pos);
//            if (param != null) {
//                parameters.set(pos - 1, new MLInputStream(param, size));
//            } else {
//                setNull(pos, Types.VARCHAR);
//            }
//        } catch (RuntimeException e) {
//            doException("error en setBinaryStream()", e);
//        }
//    }

    void doException(String message, Throwable ex) throws RuntimeException {
        RuntimeException rtme = new RuntimeException(message + " at [" + query
                + "] queryPrepared[" + queryPrepared + "]", ex);
        throw rtme;
    }
   
    /**
     * Retorna los parametros seteados hasta el momento. Los mismo se ubican segun su posicion en la query.
     *
     * @autor Matias Rege
     * @date 18/01/2008 - 12:37:26
     * @return
     */
    public List<Object> getParameters () {
        return parameters;
    }
   
    /**
     * Retorna el valor del parametro para ls posicion dada. Esta posicion no es el index del List, sino
     * la posicion del parametro en la query.
     *
     * @autor Matias Rege
     * @date 18/01/2008 - 12:40:07
     * @param index
     * @return
     */
    public Object getParameter (int index) {
        return parameters.get(index - 1);
    }

    public int getFetchDirection() throws SQLException {
        return getStatement().getFetchDirection();
    }

    public  Statement getStatement() throws SQLException {
        if (ps != null)
            return ps;
        if (cs != null)
            return cs;
        throw new SQLException("Query no preparada para ejecutar, todavia no se creo el Statement");
    }

    public int getFetchSize() throws SQLException {
        return getStatement().getFetchSize();
    }

    public int getMaxFieldSize() throws SQLException {
        return getStatement().getMaxFieldSize();
    }

    public int getMaxRows() throws SQLException {
        return getStatement().getMaxRows();
    }

    public int getQueryTimeout() throws SQLException {
        return getStatement().getQueryTimeout();
    }

    public void setFetchDirection(int direction) throws SQLException {
        getStatement().setFetchDirection(direction);
    }

    public void setFetchSize(int rows) throws SQLException {
        getStatement().setFetchSize(rows);
    }

    public void setMaxFieldSize(int max) throws SQLException {
        getStatement().setMaxFieldSize(max);
    }

    public void setMaxRows(int max) throws SQLException {
        getStatement().setMaxRows(max);
    }

    public void setQueryTimeout(int seconds) throws SQLException {
        getStatement().setQueryTimeout(seconds);
    }
   
    /**
     * Setear parametros invocando a los metodos del objeto.
     *
     * @autor Matias Rege
     * @throws SQLNestedException
     */
//    protected void setParameters(Collection<PropertyDto> parameters) throws SQLException {
//        for (PropertyDto propertyDto : parameters) {
//            try {
//                BeanUtils.setProperty(this, propertyDto.getName(), propertyDto.getValue());
//            } catch (Exception e) {
//                throw new SQLNestedException ("Error al setear propiedades ["+parameters+"]", e);
//            }
//        }
//    }
}
