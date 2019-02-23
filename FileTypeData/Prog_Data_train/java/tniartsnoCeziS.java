package validator;

import javax.validation.Constraint;
import javax.validation.Payload;
import java.lang.annotation.*;

/**
 * @author ajay
 */

@Target(ElementType.FIELD)
@Retention(RetentionPolicy.RUNTIME)
@Documented
@Constraint(validatedBy = SizeConstraintValidator.class)
public @interface SizeConstraint {
    int min();
    int max();
    String message() default "{validator.SizeConstraint}";
    Class<?>[] groups() default {};
    Class<? extends Payload>[] payload() default {};
}
