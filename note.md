## define 和 const 定义常量的差别
 1. 版本差异
 - 首先，毫无疑问的是，两种定义常量的方式之间存在版本差异，函数define()在PHP4和PHP5中均可使用，关键字const只能在PHP 5.3.0及其后的版本中使用
 2. 定义位置的区别
 - 由于函数define()定义的常量是在执行define()函数时定义的；
 - 因此可以在函数内、循环内、if语句内等函数能够被调用的任何地方使用define()函数定义常量。
 - 与define()不同的是，由于const关键字定义的常量是在编译时定义的；
 - 因此const关键字定义常量必须处于最顶端的作用区域。
 - 这也就意味着不能在函数内、循环内以及if语句之内用const来定义常量。
 3. 对值的表达式支持的差异
 - 虽然关键字const和define()定义的常量值都只能为null或标量数据(boolean，integer，float和string类型)以及resource类型(不推荐定义resource类型的常量，否则可能出现无法预知的结果)。
 - 不过，由于关键字const定义常量是在编译时定义的；
 - 因此const关键字定义的常量值的表达式中不支持算术运算符、位运算符、比较运算符等多种运算符；
 - 而这些运算符在define()函数定义常量时都是可以直接使用的。
 4. 对字符大小写敏感的支持差异
 - 除上述3个区别外，还有一个不太起眼的区别。- 函数define()可以接收第3个参数，如果该参数为true，则表示常量名的大小写不敏感。
 - 而使用const关键字定义常量却没有提供类似的功能。
## 命名空间使用注意事项
 1. 当我们require一个带有命名空间的类并且和当前文件所属命名空间不同时，实例化该类时需要带上该类的命名空间，除非use了该类；