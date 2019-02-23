# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('layouts', '0002_auto_20151019_0848'),
    ]

    operations = [
        migrations.AddField(
            model_name='table',
            name='chairs',
            field=models.IntegerField(default=1),
        ),
        migrations.AlterField(
            model_name='restaurant',
            name='rooms',
            field=models.ManyToManyField(to='layouts.Room', blank=True),
        ),
        migrations.AlterField(
            model_name='room',
            name='tables',
            field=models.ManyToManyField(to='layouts.Table', blank=True),
        ),
        migrations.AlterField(
            model_name='table',
            name='tableType',
            field=models.CharField(max_length=2, choices=[(b'CI', b'Circlar Table'), (b'SQ', b'Square Table'), (b'VR', b'Vertically Rectangular Table'), (b'HR', b'Horizontally Rectangular Table')]),
        ),
    ]
