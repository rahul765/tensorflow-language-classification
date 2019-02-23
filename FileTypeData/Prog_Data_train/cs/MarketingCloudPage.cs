using System;
using OpenQA.Selenium;
using OpenQA.Selenium.Support.UI;

namespace sso_acceptance_tests.Pages
{
    public class MarketingCloudPage : IPageObject
    {
        private const string MarketingCloudUrl = "https://mc.s1.qa1.exacttarget.com";
        private const string Username = "[ENTER YOUR USERNAME HERE]";
        private const string Password = "[ENTER YOUR PASSWORD HERE]";

        private readonly IWebDriver _browser;

        public MarketingCloudPage(IWebDriver browser)
        {
            _browser = browser;
        }

        private IWebElement UsernameTextField
        {
            get { return _browser.FindElement(By.Id("username")); }
        }

        private IWebElement PasswordTextField
        {
            get { return _browser.FindElement(By.Id("password")); }
        }

        private IWebElement LoginButton
        {
            get { return _browser.FindElement(By.ClassName("login-submit-btn")); }
        }

        private IWebElement AdvertisingStudioDropdownLink
        {
            get
            {
                var wait = new WebDriverWait(_browser, new TimeSpan(0, 0, 30));
                wait.Until(browser => browser.FindElement(By.ClassName("app_group_10")));

                return _browser.FindElement(By.ClassName("app_group_10"));
            }
        }

        private IWebElement AdvertisingStudioLink
        {
            get
            {
                var wait = new WebDriverWait(_browser, new TimeSpan(0, 0, 30));
                wait.Until(browser => browser.FindElement(By.LinkText("Social Com (Liono)")));

                return _browser.FindElement(By.LinkText("Social Com (Liono)"));
            }
        }

        public void Login()
        {
            _browser.Navigate().GoToUrl(MarketingCloudUrl);
            
            UsernameTextField.SendKeys(Username);
            PasswordTextField.SendKeys(Password);
            LoginButton.Click();
        }

        public void SelectAdvertisingStudio()
        {
            AdvertisingStudioDropdownLink.Click();
            AdvertisingStudioLink.Click();
        }
    }
}
