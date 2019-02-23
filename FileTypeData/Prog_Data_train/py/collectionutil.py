# /usr/bin/python
# -*- coding:utf-8 -*-


def print_map(map_item,line_sep=False):
	for k in map_item:
		print 'key:',k,
		if not line_sep:
			print 'val:',map_item[k]
		else:
			print ''
			print 'val:',map_item[k]


