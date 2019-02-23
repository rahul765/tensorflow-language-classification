package pl.edu.agh.soa.core.service;

import pl.edu.agh.soa.core.bean.Payment;

import javax.ejb.Remote;

import java.util.List;

/**
 * Created by Ala Czyz.
 */
@Remote
public interface PaymentManageService {
    List<Payment> getPaymentsList(Long userId);
    List<Payment> getPaymentsList();
    List<Payment> getUnpaidPaymentsList(Long userId);
    List<Payment> getOverduePaymentsList(Long userId);
    Payment getPayment(Long userId, Long paymentId);
    String getPaymentStatus(Long userId, Long paymentId);
	void deletePaymentByReservationId(Long id);


}
