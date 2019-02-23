package org.utd.bd.recsys.bean;

import java.math.BigDecimal;

public class SimilarTrack {

	private Track track;
	private BigDecimal similarity;

	public Track getTrack() {
		return track;
	}

	public void setTrack(Track track) {
		this.track = track;
	}

	public BigDecimal getSimilarity() {
		return similarity;
	}

	public void setSimilarity(BigDecimal similarity) {
		this.similarity = similarity;
	}

}
