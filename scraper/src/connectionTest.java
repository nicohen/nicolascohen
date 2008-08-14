import java.sql.SQLException;

import database.SQLCursor;
import database.SQLExecute;

public class connectionTest {
	public static void main(String[] args) throws SQLException {
		SQLCursor sql = null;
		
		try {
			SQLExecute exe = new SQLExecute("insert into prueba (nombre,edad) values('javier',35)");
			exe.execute();
//			exe.close();
			sql = new SQLCursor("select nombre,edad from prueba");
			sql.setString(1, "nicolas");
			while (sql.next()) {
				System.out.println(sql.getString("nombre")+" "+sql.getInt("edad"));
			}
		} finally {
            sql.close();
		}
		
	}
}
		