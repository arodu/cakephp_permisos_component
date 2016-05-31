# cakephp_permisos_component
CakePhp PermisosComponent

## Example

```php
<?php if($this->Permiso->hasPermission(array('user'))){ ?>
	<h2>Si Tiene permisos </h2>
<?php } ?>

<?php echo $this->Permiso->link('Permiso', array('controller'=>'docentes','action'=>'index')); ?>
```
