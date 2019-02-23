package cn.ms.coon;

import org.junit.Assert;
import org.junit.Test;

import cn.ms.coon.redis.RedisCoonFactory;
import cn.ms.coon.zookeeper.ZookeeperCoonFactory;
import cn.ms.neural.extension.ExtensionLoader;

public class MregFactoryTest {

	@Test
	public void redisRegistryFactory() {
		CoonFactory coonFactory = ExtensionLoader.getLoader(CoonFactory.class).getExtension("redis");
		Assert.assertTrue(coonFactory instanceof RedisCoonFactory);
	}

	@Test
	public void zookeeperRegistryFactory() {
		CoonFactory coonFactory = ExtensionLoader.getLoader(CoonFactory.class).getExtension("zookeeper");
		Assert.assertTrue(coonFactory instanceof ZookeeperCoonFactory);
	}

}
