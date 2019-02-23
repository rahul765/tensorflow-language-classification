package util.validate;

/*
 *@author Michael Urban
 * @version Beta 1
 * @see WantsValidationStatus
 */
public interface WantsValidationStatus {
    void validateFailed();  // Called when a component has failed validation.

    void validatePassed();  // Called when a component has passed validation.
}
