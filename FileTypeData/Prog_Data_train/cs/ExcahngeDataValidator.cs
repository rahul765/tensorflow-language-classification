using System;
using System.Collections.Generic;
using System.Linq;
using wipm.exchangestats.data.ingress.interfaces;

namespace wipm.exchangestats.data.ingress.core {

    public class ExchangeDataValidationRequest {

        public readonly IEnumerable<ExchangeModel> Exchanges;
        public readonly ExchangeData Data;
        public readonly bool CheckCodeIsUnique;


        public ExchangeDataValidationRequest
                ( IEnumerable<ExchangeModel> exchanges
                , ExchangeData data
                , bool checkCodeIsUnique ) {

            if ( exchanges == null ) throw new ArgumentNullException( nameof( exchanges ) );
            if ( data == null ) throw new ArgumentNullException( nameof( exchanges ) );


            this.Exchanges = exchanges;
            this.Data = data;
            this.CheckCodeIsUnique = checkCodeIsUnique;
        }

    }


    public class ExchangeCodeNotSpecified : StateChangeError { }
    public class ExchangeCodeIsNotUnique : StateChangeError { }
    public class ExchangeNameNotSpecified : StateChangeError { }
    public class ExchangeNameIsNotUnique : StateChangeError { }


    public class ExchangeDataValidator {

        public static IEnumerable<StateChangeError> Validate
                                                      ( ExchangeDataValidationRequest request ) {

            if ( request == null ) throw new ArgumentNullException( nameof( request ) );


            var errors = new List<StateChangeError>();

            if ( string.IsNullOrWhiteSpace( request.Data.Code )) {
                errors.Add( new ExchangeCodeNotSpecified() );
            }

            if ( request.Exchanges.Any( e => request.CheckCodeIsUnique && e.Code.Equals( request.Data.Code ) ) ) {
                errors.Add( new ExchangeCodeIsNotUnique() );
            }

            if ( string.IsNullOrWhiteSpace( request.Data.Name ) ) {
                errors.Add( new ExchangeNameNotSpecified() );
            }

            if ( request.Exchanges.Any( e => !e.Code.Equals( request.Data.Code ) &&  e.Name.Equals( request.Data.Name ) ) ) {
                errors.Add( new ExchangeNameIsNotUnique() );
            }

            return errors;
        }

    }
}
