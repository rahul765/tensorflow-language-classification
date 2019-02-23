package pl.edu.agh.soa.ba.form;

import java.math.BigDecimal;

import pl.edu.agh.soa.core.bean.RoomType;

/**
 * @author Piotr Konsek
 *
 */
public class RoomTypeForm {
	private RoomType roomType;
	
	public RoomTypeForm(){
		roomType = new RoomType();
	}
	
	public String getDescription() {
		return roomType.getDescription();
	}

	public String getName() {
		return roomType.getName();
	}

	public BigDecimal getPrice() {
		return roomType.getPrice();
	}

	public void setDescription(String description) {
		roomType.setDescription(description);
	}

	public void setName(String name) {
		roomType.setName(name);
	}

	public void setPrice(BigDecimal price) {
		roomType.setPrice(price);
	}

	public RoomType getRoomType() {
		return roomType;
	}

	public void setRoomType(RoomType roomType) {
		this.roomType = roomType;
	}
}
