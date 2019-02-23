from django.core.management.base import BaseCommand
from django.db.models import get_models, get_app


class Command(BaseCommand):
    args = '<app app ...>'
    help = 'list all the permissions'

    def handle(self, *args, **options):

        if not args:
            apps = []
            for model in get_models():
                apps.append(get_app(model._meta.app_label))
        else:
            apps = []
            for arg in args:
                apps.append(get_app(arg))

        app_custom_perms = []
        for model in get_models():
            if model._meta.permissions:
                app_custom_perms.append({'app': model._meta.app_label,
                                         'perms': model._meta.permissions})

        from pprint import pprint
        pprint(app_custom_perms)
