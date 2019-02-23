package pl.edu.agh.soa.core.service.impl;

import javax.ejb.EJB;
import javax.ejb.Stateless;

import pl.edu.agh.soa.core.bean.Payment;
import pl.edu.agh.soa.core.dao.PaymentDAO;
import pl.edu.agh.soa.core.service.PaymentConductService;

/**
 * Created by Ala Czyz.
 */
@Stateless
public class PaymentConductServiceImpl implements PaymentConductService {

    @EJB
    PaymentDAO paymentDAO;

    @Override
    public Payment payByCreditCard(Long paymentId, String creditCard) {
        Payment payment = paymentDAO.getPayment(paymentId);
        payment.setStatus(Payment.Status.PAID);
        return payment;
    }

    @Override
    public Payment payByTransfer(Long paymentId, String bank) {
        Payment payment = paymentDAO.getPayment(paymentId);
        payment.setStatus(Payment.Status.PAID);
        return payment;
    }
}
