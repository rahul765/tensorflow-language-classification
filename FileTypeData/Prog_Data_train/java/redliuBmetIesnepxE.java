package codingtest.dto;

import codingtest.model.Expense;

public class ExpenseItemBuilder extends ExpenseItem.ExpenseItemBuilder {

    public static ExpenseItemBuilder expenseItem() {
        return new ExpenseItemBuilder();
    }

    public ExpenseItemBuilder forExpense(Expense expense) {
        this
            .id(expense.getId())
            .date(expense.getDate())
            .amount(expense.getAmount())
            .reason(expense.getReason());
        return this;
    }
}
