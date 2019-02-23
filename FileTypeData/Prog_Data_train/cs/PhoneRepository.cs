using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using NLayerApp.DAL.Entities;
using NLayerApp.DAL.Interfaces;
using System.Data.Entity;
using NLayerApp.DAL.EF;

namespace NLayerApp.DAL.Repositories
{
    public class PhoneRepository : IRepository<Phone>
    {
        private MobileContext db;

        public PhoneRepository(MobileContext context)
        {
            this.db = context;
        }

        public void Create(Phone item)
        {
            db.Phones.Add(item);
        }

        public void Delete(int id)
        {
            Phone phone = db.Phones.Find(id);
            if(phone != null)
            {
                db.Phones.Remove(phone);
            }
        }

        public IEnumerable<Phone> Find(Func<Phone, bool> predicate)
        {
            return db.Phones.Where(predicate).ToList();
        }

        public Phone Get(int id)
        {
            return db.Phones.Find(id);
        }

        public IEnumerable<Phone> GetAll()
        {
            return db.Phones;
        }

        public void Update(Phone item)
        {
            db.Entry(item).State = EntityState.Modified;
        }
    }
}
