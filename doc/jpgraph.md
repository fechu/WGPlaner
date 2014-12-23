# JpGraph Library

The JpGraph Library ([http://jpgraph.net](http://jpgraph.net)) is used for graph generation in the API module. 

## Fix for GD Extension

The library expects PHP to be compiled with the GD extension. If it is not compiled, only linked
to it the graph generation will fail due to an Exception that is thrown. To fix this I modified the
library by commenting line 110 in the `gd_image.inc.php` file out. The following line is now 
commented: 

    JpGraphError::RaiseL(25128);//('The function imageantialias() is not available in your PHP installation. Use the GD version that comes with PHP and not the standalone version.')

This will prevent that the exception is thrown. The downside of this is, that it is not possible to have the graphs antialiased. 