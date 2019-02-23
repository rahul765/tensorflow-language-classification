/**
 * 
 */
package io.pivotal.ce.gemfire.fastfootshoes.repositories;

import io.pivotal.ce.gemfire.fastfootshoes.model.MarkUp;

import org.springframework.data.gemfire.repository.GemfireRepository;

/**
 * @author lshannon
 *
 */
public interface MarkUpRepository extends GemfireRepository<MarkUp, String> {

}
