package webapp.satrt;


import com.lzbruby.webapp.WebAppTestServer;
import com.lzbruby.webapp.lunch.TomcatWebAppTestServer;

/**
 * 功能描述：tomcat测试启动器
 *
 * @author: Zhenbin.Li
 * email： lizhenbin08@sina.cn
 * company：org.lzbruby
 * Date: 15/8/24 Time: 16:15
 */
public class TomcatWebAppTestStarter {

    public static void main(String[] args) {
        WebAppTestServer server = new TomcatWebAppTestServer();
        server.start();
    }
}
