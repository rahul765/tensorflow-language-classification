package com.springapp.controllers;

import hibernate.Itemsorder;
import org.springframework.stereotype.Controller;
import org.springframework.ui.ModelMap;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;

/**
 * Created by george on 11/26/2014.
 */

@Controller
@RequestMapping("/paymentpage")
public class PaymentPage {
    @RequestMapping(method = RequestMethod.GET)
    public String paymentPage(ModelMap modelMap) {
        modelMap.addAttribute("itemsOrder", new Itemsorder());
        return "paymentPage";
    }

    @RequestMapping(method = RequestMethod.POST)
    public String paymentPageComplete() {
        //send receipt to the users mail
        return "paymentSuccessful";
    }

}
