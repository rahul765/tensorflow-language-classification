package ui.client.view;

import model.Equimpent;
import ui.client.model.ListaTowarowModel;

import javax.swing.*;

public class WypozyczeniaTopPanel extends JPanel {
    JComboBox<Equimpent> equimpentCB;

    public WypozyczeniaTopPanel() {
        initializeComponents();
    }

    private void initializeComponents() {
        JLabel wybierzSprzetLB = new JLabel("Wybierz sprzęt do wypożyczenia");

        ListaTowarowModel model = new ListaTowarowModel();
        equimpentCB = new JComboBox<Equimpent>(model);

        add(wybierzSprzetLB);
        add(equimpentCB);
    }

    public JComboBox<Equimpent> getEquimpentCB() {
        return equimpentCB;
    }
}
