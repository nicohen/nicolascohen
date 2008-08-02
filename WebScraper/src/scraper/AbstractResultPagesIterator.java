package scraper;

import java.util.Iterator;

import dto.ResultPageDto;

public abstract interface AbstractResultPagesIterator extends Iterator<ResultPageDto> {

	@Override
	public abstract boolean hasNext();

	@Override
	public abstract ResultPageDto next();

	@Override
	public void remove();
}
