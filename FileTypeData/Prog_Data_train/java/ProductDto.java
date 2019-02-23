package es.mhp.dtos;

import org.hibernate.validator.constraints.NotBlank;

import javax.validation.constraints.NotNull;
import javax.validation.constraints.Size;

public class ProductDto extends AbstractDto {
    @NotNull
    @NotBlank
    @Size(max = 50)
    protected String name;
    @NotNull
    @NotBlank
    @Size(max = 50)
    protected String brand;

    protected UserDto user;

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getBrand() {
        return brand;
    }

    public void setBrand(String brand) {
        this.brand = brand;
    }

    public UserDto getUser() {
        return user;
    }

    public void setUser(UserDto user) {
        this.user = user;
    }
}