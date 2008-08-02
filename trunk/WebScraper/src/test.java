
public class test {
	public static void main(String[] args) {
		String numero = "camara-pg23.html";
		String[] palabra = numero.split("\\D");
		System.out.println(palabra[0]);
	}
}
