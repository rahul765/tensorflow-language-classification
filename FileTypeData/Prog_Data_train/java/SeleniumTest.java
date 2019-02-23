package com.tui.demo;

import com.thoughtworks.selenium.*;
import org.junit.After;
import org.junit.Before;
import org.junit.Test;
import org.openqa.selenium.server.SeleniumServer;

public class SeleniumTest extends SeleneseTestCase{
	private static SeleniumServer seleniumServer;
	@Before
	public void setUp() throws Exception {
		selenium = new DefaultSelenium("delvmplmred06", 4444, "*firefox", "http://www.google.co.in/");
        seleniumServer = new SeleniumServer();
        seleniumServer.start();
		selenium.start();
	}

	@Test
	public void testGoogleSearch() throws Exception {
		selenium.open("/");
		selenium.click("link=Advanced search");
		selenium.waitForPageToLoad("30000");
		selenium.type("as_q", "selftechy, selenium");
		selenium.click("//input[@value='Advanced Search']");
		selenium.waitForPageToLoad("30000");
	}

	@After
	public void tearDown() throws Exception {
		selenium.stop();
		seleniumServer.stop();
	}

}
