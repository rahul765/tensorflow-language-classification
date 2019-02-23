namespace XamlPowerToys.Fakes.Books {
    using System;

    public class Book : ObservableObject, IComparable<Book>, IEquatable<Book> {

        Double _cost;
        DateTime _datePublished;
        String _description = String.Empty;
        Format _format;
        String _purchasedFrom = String.Empty;
        Int32 _rating;
        String _title = String.Empty;
        String _url = String.Empty;

        public Double Cost {
            get { return _cost; }
            set {
                _cost = value;
                RaisePropertyChanged();
            }
        }

        public String CostText {
            get {
                if (this.Cost > 0d) {
                    return this.Cost.ToString("C");
                }
                return "Free";
            }
        }

        public DateTime DatePublished {
            get { return _datePublished; }
            set {
                _datePublished = value;
                RaisePropertyChanged();
            }
        }

        public String Description {
            get { return _description; }
            set {
                _description = value;
                RaisePropertyChanged();
            }
        }

        public Format Format {
            get { return _format; }
            set {
                _format = value;
                RaisePropertyChanged();
            }
        }

        public Int32 Id { get; set; }

        public String Image { get; set; }

        public String PurchasedFrom {
            get { return _purchasedFrom; }
            set {
                _purchasedFrom = value;
                RaisePropertyChanged();
            }
        }

        public Int32 Rating {
            get { return _rating; }
            set {
                _rating = value;
                RaisePropertyChanged();
            }
        }

        public String ShortDescription {
            get {
                if (this.Description.Length <= 50) {
                    return this.Description;
                }
                if (this.Description.Substring(50, 1) == " ") {
                    return $"{this.Description.Substring(0, 50)} ...";
                }
                var index = 51;
                while (index < 55) {
                    if (this.Description.Substring(index, 1) == " ") {
                        break;
                    }
                    index += 1;
                }
                return $"{this.Description.Substring(0, index)}...";
            }
        }

        public String Title {
            get { return _title; }
            set {
                _title = value;
                RaisePropertyChanged();
            }
        }

        public String Url {
            get { return _url; }
            set {
                _url = value;
                RaisePropertyChanged();
            }
        }

        public Book() {
        }

        public Int32 CompareTo(Book other) {
            if (this.Title == other.Title) {
                return 0;
            }
            return String.Compare(this.Title, other.Title, StringComparison.Ordinal);
        }

        public Boolean Equals(Book other) {
            return this.Title.Equals(other.Title);
        }

    }
}
