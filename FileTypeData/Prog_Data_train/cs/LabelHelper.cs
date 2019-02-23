namespace XamlPowerToys.Model.CodeGeneration.Infrastructure {
    using System;
    using System.Text;
    using XamlPowerToys.Model.Infrastructure;

    public class LabelHelper {

        readonly ProjectType _projectType;

        public LabelHelper(ProjectType projectType) {
            _projectType = projectType;
        }

        public String MakeTag(String text, String lableImageName, String labelWidthText, Int32? parentGridColumn, Int32? parentGridRow) {
            if (String.IsNullOrWhiteSpace(text)) {
                return String.Empty;
            }

            var sb = new StringBuilder();

            if (String.IsNullOrWhiteSpace(lableImageName)) {
                if (_projectType == ProjectType.Xamarin) {
                    sb.Append($"<Label Text=\"{text}\" ");
                } else if (_projectType == ProjectType.Uwp) {
                    sb.Append($"<TextBlock Text=\"{text}\" ");
                } else {
                    sb.Append($"<Label Content=\"{text}\" ");
                }
            } else {
                sb.Append($"<Image Source=\"{lableImageName}\" ");
            }

            if (parentGridColumn != null && parentGridColumn != 0) {
                sb.Append($"Grid.Column=\"{parentGridColumn.Value}\" ");
            }
            if (parentGridRow != null && parentGridRow != 0) {
                sb.Append($"Grid.Row=\"{parentGridRow.Value}\" ");
            }

            if (String.IsNullOrWhiteSpace(lableImageName)) {
                sb.Append(labelWidthText);
            }

            sb.Append("/>");
            return sb.ToString().CompactString();
        }

    }
}
