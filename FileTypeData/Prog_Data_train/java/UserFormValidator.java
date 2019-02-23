package com.peoplewearus.web.spring.validation;

import com.peoplewearus.web.spring.domain.User;
import org.springframework.stereotype.Component;
import org.springframework.validation.Errors;
import org.springframework.validation.Validator;


@Component
public final class UserFormValidator implements Validator {
    @Override
    public boolean supports(Class<?> clazz) {
        return User.class.equals(clazz);
    }

    @Override
    public void validate(Object target, Errors errors) {
        User user = (User) target;

        if ("red".equals(user.getEmail())) {
            errors.rejectValue("email", "email.taken");
        }
    }

}
