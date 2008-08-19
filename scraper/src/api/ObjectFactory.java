package api;

import api.bo.spider.ISpiderBo;
import api.dao.spider.ISpiderDao;
import app.bo.spider.SpiderBo;
import app.dao.spider.SpiderDao;

public class ObjectFactory {

	public static Object getObject(Object interfaz) {

		if (interfaz instanceof ISpiderBo) {
			return SpiderBo.class;
		} else if (interfaz instanceof ISpiderDao) {
			return SpiderDao.class;
		}
		
		return null;
	}
	
}
