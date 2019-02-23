package com.clouway.aop;

import org.aopalliance.intercept.MethodInterceptor;
import org.aopalliance.intercept.MethodInvocation;

/**
 * @author Dimitar Dimitrov (dimitar.dimitrov045@gmail.com)
 */
public class ShellGasolineStation implements MethodInterceptor {

  public Object invoke(MethodInvocation methodInvocation) throws Throwable {
    System.out.println("Fill gasoline from Shell gasoline station");
    methodInvocation.proceed();
    return null;
  }
}
