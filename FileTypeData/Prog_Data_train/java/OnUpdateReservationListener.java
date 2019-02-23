package ui.reservation.update;

import patient.Reservation;

public interface OnUpdateReservationListener {
    public void insertedNewReservation(Reservation reservation);

    public void editedReservation(Reservation reservation);

    public void deletedReservation(Reservation reservation);
}
