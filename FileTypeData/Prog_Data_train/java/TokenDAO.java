package pl.edu.agh.soa.core.dao;

import javax.ejb.Local;

import pl.edu.agh.soa.core.bean.Token;

@Local
public interface TokenDAO {
	public boolean saveToken(Token token);
	public Token updateToken(Token token);
	public Token getTokenByMail(String mail);	
	public Token getToken(String token);
	public boolean checkToken(String token);
}
