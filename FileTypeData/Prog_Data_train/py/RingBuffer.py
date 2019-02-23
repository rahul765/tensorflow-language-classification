class RingBuffer:
    """
    定义一个未填满的缓存类
    """
    def __init__(self, size_max):
        self.size = size_max
        self.data = []

    class __Full:
    '''
    定义一个填满缓存时处理
    '''
        def append(self, x):
            self.data[self.cur] = x
            self.cur = (self.cur+1) % self.size

        def tolist(self):
            return self.data[self.cur:]+self.data[:self.cur]

    def append(self, x):
        self.data.append(x)
        if len(self.data) == self.size:
            self.cur = 0
            self.__class__ = self.__Full
            """永久性的将当前类切换填满的缓存类，关键部分"""
    def tolist(self):
        return self.data
