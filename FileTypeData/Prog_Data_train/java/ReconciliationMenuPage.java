package com.epam.smoke_tests.smoke_test_pages.ui_tests.reconciliations;

import com.epam.main.Page;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

/**
 * Created with IntelliJ IDEA.
 * User: Oleksandr_Kara
 * Date: 4/8/14
 * Time: 3:46 PM
 * To change this template use File | Settings | File Templates.
 */
public class ReconciliationMenuPage extends Page {

    @FindBy(xpath = "//a[contains(@href,'property_reconciliations')]")
    WebElement propertyReconciliationLing;

    public PropertyReconciliationPage clickToPropertyReconciliationsLink() {
        propertyReconciliationLing.click();
        return new PropertyReconciliationPage();
    }
}
