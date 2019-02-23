# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('layouts', '0001_initial'),
    ]

    operations = [
        migrations.AddField(
            model_name='restaurant',
            name='rooms',
            field=models.ManyToManyField(to='layouts.Room', null=True, blank=True),
        ),
        migrations.AddField(
            model_name='room',
            name='tables',
            field=models.ManyToManyField(to='layouts.Table', null=True, blank=True),
        ),
    ]
